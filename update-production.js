#!/usr/bin/env node

/**
 * Production Update Manager
 * 
 * Handles automated package updates, version management, and optimization
 * for the Friends of Children Ministries platform in production environments.
 * 
 * @author Friends of Children Ministries Development Team
 * @version 2.0.0
 */

const { execSync, spawn } = require('child_process');
const fs = require('fs');
const path = require('path');
const os = require('os');

class ProductionUpdateManager {
    constructor() {
        this.packageJson = this.loadJsonFile('package.json');
        this.composerJson = this.loadJsonFile('composer.json');
        this.currentVersion = this.packageJson.version;
        this.startTime = new Date();
        this.logFile = path.join('storage', 'logs', `update-${this.formatDate(this.startTime)}.log`);
        this.errors = [];
        this.warnings = [];
        
        this.ensureLogDirectory();
    }

    /**
     * Load and parse JSON file with error handling
     */
    loadJsonFile(filePath) {
        try {
            return JSON.parse(fs.readFileSync(filePath, 'utf8'));
        } catch (error) {
            this.logError(`Failed to load ${filePath}: ${error.message}`);
            process.exit(1);
        }
    }

    /**
     * Ensure log directory exists
     */
    ensureLogDirectory() {
        const logDir = path.dirname(this.logFile);
        if (!fs.existsSync(logDir)) {
            fs.mkdirSync(logDir, { recursive: true });
        }
    }

    /**
     * Enhanced logging with file output and console display
     */
    log(message, level = 'info') {
        const timestamp = new Date().toISOString();
        const logEntry = `[${timestamp}] [${level.toUpperCase()}] ${message}`;
        
        // Console output with colors
        const colors = {
            info: '\x1b[36m',
            success: '\x1b[32m',
            warning: '\x1b[33m',
            error: '\x1b[31m',
            debug: '\x1b[90m',
            reset: '\x1b[0m'
        };
        
        console.log(`${colors[level] || colors.info}${logEntry}${colors.reset}`);
        
        // File output
        try {
            fs.appendFileSync(this.logFile, logEntry + '\n');
        } catch (error) {
            console.error(`Failed to write to log file: ${error.message}`);
        }
    }

    /**
     * Log error and add to error collection
     */
    logError(message) {
        this.errors.push(message);
        this.log(message, 'error');
    }

    /**
     * Log warning and add to warning collection
     */
    logWarning(message) {
        this.warnings.push(message);
        this.log(message, 'warning');
    }

    /**
     * Execute command with enhanced error handling and logging
     */
    async executeCommand(command, description, options = {}) {
        const { 
            allowFailure = false, 
            timeout = 300000, // 5 minutes default
            cwd = process.cwd(),
            silent = false 
        } = options;

        if (!silent) {
            this.log(`Executing: ${description}`, 'info');
            this.log(`Command: ${command}`, 'debug');
        }

        return new Promise((resolve, reject) => {
            const startTime = Date.now();
            
            try {
                const child = spawn('cmd', ['/c', command], {
                    cwd,
                    stdio: silent ? 'pipe' : 'inherit',
                    shell: true
                });

                let output = '';
                let errorOutput = '';

                if (silent) {
                    child.stdout.on('data', (data) => {
                        output += data.toString();
                    });

                    child.stderr.on('data', (data) => {
                        errorOutput += data.toString();
                    });
                }

                const timeoutId = setTimeout(() => {
                    child.kill('SIGTERM');
                    const message = `Command timed out after ${timeout}ms: ${description}`;
                    if (allowFailure) {
                        this.logWarning(message);
                        resolve({ success: false, output: '', error: message });
                    } else {
                        reject(new Error(message));
                    }
                }, timeout);

                child.on('close', (code) => {
                    clearTimeout(timeoutId);
                    const duration = Date.now() - startTime;
                    
                    if (code === 0) {
                        if (!silent) {
                            this.log(`✓ ${description} completed (${duration}ms)`, 'success');
                        }
                        resolve({ success: true, output, error: '' });
                    } else {
                        const message = `${description} failed with exit code ${code}`;
                        if (errorOutput) {
                            this.log(`Error output: ${errorOutput}`, 'debug');
                        }
                        
                        if (allowFailure) {
                            this.logWarning(message);
                            resolve({ success: false, output, error: errorOutput || message });
                        } else {
                            reject(new Error(message));
                        }
                    }
                });

                child.on('error', (error) => {
                    clearTimeout(timeoutId);
                    const message = `${description} failed: ${error.message}`;
                    
                    if (allowFailure) {
                        this.logWarning(message);
                        resolve({ success: false, output: '', error: error.message });
                    } else {
                        reject(error);
                    }
                });

            } catch (error) {
                const message = `Failed to execute ${description}: ${error.message}`;
                if (allowFailure) {
                    this.logWarning(message);
                    resolve({ success: false, output: '', error: error.message });
                } else {
                    reject(error);
                }
            }
        });
    }

    /**
     * Validate environment and prerequisites
     */
    async validateEnvironment() {
        this.log('Validating environment and prerequisites', 'info');
        
        const checks = [
            { command: 'php --version', name: 'PHP' },
            { command: 'composer --version', name: 'Composer' },
            { command: 'node --version', name: 'Node.js' },
            { command: 'npm --version', name: 'NPM' }
        ];

        const results = {};
        
        for (const check of checks) {
            try {
                const result = await this.executeCommand(check.command, `Check ${check.name}`, { 
                    silent: true, 
                    allowFailure: true 
                });
                
                if (result.success) {
                    const version = result.output.split('\n')[0].trim();
                    results[check.name.toLowerCase()] = version;
                    this.log(`${check.name}: ${version}`, 'success');
                } else {
                    this.logError(`${check.name} not found or not working`);
                    return false;
                }
            } catch (error) {
                this.logError(`Failed to check ${check.name}: ${error.message}`);
                return false;
            }
        }

        // Validate required files
        const requiredFiles = ['composer.json', 'package.json', 'artisan'];
        for (const file of requiredFiles) {
            if (!fs.existsSync(file)) {
                this.logError(`Required file not found: ${file}`);
                return false;
            }
        }

        this.systemInfo = {
            platform: os.platform(),
            arch: os.arch(),
            nodeVersion: results.nodejs,
            phpVersion: results.php,
            composerVersion: results.composer,
            npmVersion: results.npm
        };

        this.log('Environment validation completed successfully', 'success');
        return true;
    }

    /**
     * Calculate next version based on increment type
     */
    calculateNextVersion(type = 'patch') {
        const parts = this.currentVersion.split('.').map(Number);
        let [major, minor, patch] = parts;

        switch (type.toLowerCase()) {
            case 'major':
                major += 1;
                minor = 0;
                patch = 0;
                break;
            case 'minor':
                minor += 1;
                patch = 0;
                break;
            case 'patch':
            default:
                patch += 1;
                break;
        }

        const newVersion = `${major}.${minor}.${patch}`;
        this.log(`Version increment: ${this.currentVersion} → ${newVersion} (${type})`, 'info');
        return newVersion;
    }

    /**
     * Update version in package files
     */
    updatePackageVersions(newVersion) {
        try {
            // Update package.json
            this.packageJson.version = newVersion;
            fs.writeFileSync('package.json', JSON.stringify(this.packageJson, null, 2) + '\n');
            
            // Update composer.json if it has a version field
            if (this.composerJson.version) {
                this.composerJson.version = newVersion;
                fs.writeFileSync('composer.json', JSON.stringify(this.composerJson, null, 2) + '\n');
            }
            
            this.log(`Package versions updated to ${newVersion}`, 'success');
            return true;
        } catch (error) {
            this.logError(`Failed to update package versions: ${error.message}`);
            return false;
        }
    }

    /**
     * Create backup of critical files
     */
    createBackup() {
        this.log('Creating backup of critical files', 'info');
        
        const backupDir = path.join('storage', 'backups', `update-${this.formatDate(this.startTime)}`);
        
        try {
            if (!fs.existsSync(backupDir)) {
                fs.mkdirSync(backupDir, { recursive: true });
            }

            const filesToBackup = [
                'package.json',
                'composer.json',
                'composer.lock',
                'package-lock.json'
            ];

            for (const file of filesToBackup) {
                if (fs.existsSync(file)) {
                    fs.copyFileSync(file, path.join(backupDir, file));
                    this.log(`Backed up: ${file}`, 'debug');
                }
            }

            this.backupPath = backupDir;
            this.log(`Backup created at: ${backupDir}`, 'success');
            return true;
        } catch (error) {
            this.logWarning(`Backup creation failed: ${error.message}`);
            return false;
        }
    }

    /**
     * Update PHP dependencies with comprehensive error handling
     */
    async updateComposerDependencies() {
        this.log('Updating PHP dependencies via Composer', 'info');
        
        const commands = [
            {
                cmd: 'composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist',
                desc: 'Install production dependencies',
                critical: true
            },
            {
                cmd: 'composer update --no-dev --optimize-autoloader --no-interaction --with-dependencies',
                desc: 'Update dependencies',
                critical: false
            },
            {
                cmd: 'composer dump-autoload --optimize --no-dev',
                desc: 'Optimize autoloader',
                critical: true
            }
        ];

        for (const command of commands) {
            try {
                const result = await this.executeCommand(command.cmd, command.desc, {
                    allowFailure: !command.critical,
                    timeout: 600000 // 10 minutes for composer operations
                });

                if (!result.success && command.critical) {
                    throw new Error(`Critical composer operation failed: ${command.desc}`);
                }
            } catch (error) {
                if (command.critical) {
                    throw error;
                } else {
                    this.logWarning(`Non-critical composer operation failed: ${error.message}`);
                }
            }
        }

        this.log('Composer dependencies updated successfully', 'success');
    }

    /**
     * Update Node.js dependencies
     */
    async updateNodeDependencies() {
        this.log('Updating Node.js dependencies', 'info');
        
        try {
            // Check if package-lock.json exists for npm ci
            if (fs.existsSync('package-lock.json')) {
                await this.executeCommand('npm ci --production', 'Clean install production dependencies', {
                    timeout: 600000
                });
            } else {
                await this.executeCommand('npm install --production', 'Install production dependencies', {
                    timeout: 600000
                });
            }

            // Update dependencies
            await this.executeCommand('npm update --production', 'Update Node.js dependencies', {
                allowFailure: true,
                timeout: 600000
            });

            this.log('Node.js dependencies updated successfully', 'success');
        } catch (error) {
            throw new Error(`Node.js dependency update failed: ${error.message}`);
        }
    }

    /**
     * Build production assets
     */
    async buildAssets() {
        this.log('Building production assets', 'info');
        
        try {
            await this.executeCommand('npm run build', 'Build frontend assets', {
                timeout: 600000
            });
            
            // Verify build output
            if (fs.existsSync('public/build')) {
                const buildFiles = fs.readdirSync('public/build');
                this.log(`Build completed: ${buildFiles.length} files generated`, 'success');
            } else {
                this.logWarning('Build directory not found, but build command succeeded');
            }
        } catch (error) {
            throw new Error(`Asset build failed: ${error.message}`);
        }
    }

    /**
     * Run database migrations safely
     */
    async runMigrations() {
        this.log('Running database migrations', 'info');
        
        try {
            // Check migration status first
            const statusResult = await this.executeCommand('php artisan migrate:status', 'Check migration status', {
                silent: true,
                allowFailure: true
            });

            if (statusResult.success) {
                this.log('Migration status checked successfully', 'debug');
            }

            // Run migrations
            await this.executeCommand('php artisan migrate --force', 'Execute database migrations', {
                allowFailure: true
            });

            this.log('Database migrations completed', 'success');
        } catch (error) {
            this.logWarning(`Migration execution encountered issues: ${error.message}`);
        }
    }

    /**
     * Optimize Laravel application for production
     */
    async optimizeLaravel() {
        this.log('Optimizing Laravel application for production', 'info');
        
        const optimizations = [
            {
                cmd: 'php artisan config:cache',
                desc: 'Cache configuration files',
                critical: false
            },
            {
                cmd: 'php artisan route:cache',
                desc: 'Cache application routes',
                critical: false
            },
            {
                cmd: 'php artisan view:cache',
                desc: 'Cache Blade templates',
                critical: false
            },
            {
                cmd: 'php artisan event:cache',
                desc: 'Cache event listeners',
                critical: false
            },
            {
                cmd: 'php artisan optimize',
                desc: 'General Laravel optimization',
                critical: true
            }
        ];

        let successCount = 0;
        
        for (const optimization of optimizations) {
            try {
                const result = await this.executeCommand(optimization.cmd, optimization.desc, {
                    allowFailure: !optimization.critical
                });

                if (result.success) {
                    successCount++;
                } else if (optimization.critical) {
                    throw new Error(`Critical optimization failed: ${optimization.desc}`);
                }
            } catch (error) {
                if (optimization.critical) {
                    throw error;
                } else {
                    this.logWarning(`Optimization step failed: ${error.message}`);
                }
            }
        }

        this.log(`Laravel optimization completed: ${successCount}/${optimizations.length} steps successful`, 'success');
    }

    /**
     * Perform system health checks
     */
    async performHealthChecks() {
        this.log('Performing post-update health checks', 'info');
        
        const checks = [
            {
                cmd: 'php artisan about',
                desc: 'Laravel application status',
                critical: false
            },
            {
                cmd: 'php artisan config:show app.name',
                desc: 'Application configuration',
                critical: false
            }
        ];

        for (const check of checks) {
            try {
                await this.executeCommand(check.cmd, check.desc, {
                    allowFailure: true,
                    silent: true
                });
            } catch (error) {
                this.logWarning(`Health check failed: ${check.desc} - ${error.message}`);
            }
        }
    }

    /**
     * Generate comprehensive update report
     */
    generateUpdateReport(version, success = true) {
        const endTime = new Date();
        const duration = Math.round((endTime - this.startTime) / 1000);
        
        const report = {
            metadata: {
                version: version,
                timestamp: endTime.toISOString(),
                duration: `${duration}s`,
                environment: 'production',
                operation: 'update',
                success: success
            },
            system: this.systemInfo || {},
            statistics: {
                errors: this.errors.length,
                warnings: this.warnings.length,
                composer_packages: this.getComposerPackageCount(),
                npm_packages: this.getNpmPackageCount()
            },
            details: {
                errors: this.errors,
                warnings: this.warnings,
                log_file: this.logFile,
                backup_path: this.backupPath || null
            }
        };

        const reportPath = path.join('storage', 'logs', `update-report-${this.formatDate(endTime)}.json`);
        
        try {
            fs.writeFileSync(reportPath, JSON.stringify(report, null, 2));
            this.log(`Update report generated: ${reportPath}`, 'success');
        } catch (error) {
            this.logWarning(`Failed to write update report: ${error.message}`);
        }

        return report;
    }

    /**
     * Get composer package count from lock file
     */
    getComposerPackageCount() {
        try {
            const lockFile = JSON.parse(fs.readFileSync('composer.lock', 'utf8'));
            return lockFile.packages ? lockFile.packages.length : 0;
        } catch {
            return 0;
        }
    }

    /**
     * Get npm package count from lock file
     */
    getNpmPackageCount() {
        try {
            const lockFile = JSON.parse(fs.readFileSync('package-lock.json', 'utf8'));
            return lockFile.packages ? Object.keys(lockFile.packages).length : 0;
        } catch {
            return 0;
        }
    }

    /**
     * Format date for file names
     */
    formatDate(date) {
        return date.toISOString().replace(/[:.]/g, '-').split('T')[0] + '_' + 
               date.toTimeString().split(' ')[0].replace(/:/g, '-');
    }

    /**
     * Main update process orchestrator
     */
    async executeUpdate(options = {}) {
        const {
            versionType = 'patch',
            skipMigrations = false,
            skipOptimization = false,
            skipBackup = false
        } = options;

        let newVersion = null;
        let success = false;

        try {
            this.log('='.repeat(60), 'info');
            this.log('Friends of Children Ministries - Production Update', 'info');
            this.log('='.repeat(60), 'info');
            
            // Environment validation
            const envValid = await this.validateEnvironment();
            if (!envValid) {
                throw new Error('Environment validation failed');
            }

            // Create backup
            if (!skipBackup) {
                this.createBackup();
            }

            // Version management
            newVersion = this.calculateNextVersion(versionType);
            if (!this.updatePackageVersions(newVersion)) {
                throw new Error('Failed to update package versions');
            }

            // Dependency updates
            await this.updateComposerDependencies();
            await this.updateNodeDependencies();

            // Asset building
            await this.buildAssets();

            // Database migrations
            if (!skipMigrations) {
                await this.runMigrations();
            }

            // Laravel optimization
            if (!skipOptimization) {
                await this.optimizeLaravel();
            }

            // Health checks
            await this.performHealthChecks();

            success = true;
            this.log('='.repeat(60), 'success');
            this.log(`Production update v${newVersion} completed successfully!`, 'success');
            this.log('Manual deployment to hosting platform is required', 'warning');
            this.log('='.repeat(60), 'success');

        } catch (error) {
            this.logError(`Update process failed: ${error.message}`);
            this.log('='.repeat(60), 'error');
            this.log('Production update failed - check logs for details', 'error');
            this.log('='.repeat(60), 'error');
        } finally {
            // Generate report regardless of success/failure
            const report = this.generateUpdateReport(newVersion || this.currentVersion, success);
            
            if (success) {
                this.displaySuccessSummary(report);
            } else {
                this.displayFailureSummary(report);
            }
        }

        return success ? 0 : 1;
    }

    /**
     * Display success summary
     */
    displaySuccessSummary(report) {
        console.log('\n📊 Update Summary:');
        console.log(`   Version: ${report.metadata.version}`);
        console.log(`   Duration: ${report.metadata.duration}`);
        console.log(`   Warnings: ${report.statistics.warnings}`);
        console.log(`   Packages: ${report.statistics.composer_packages} PHP, ${report.statistics.npm_packages} Node.js`);
        console.log(`   Log: ${report.details.log_file}`);
        
        if (report.details.backup_path) {
            console.log(`   Backup: ${report.details.backup_path}`);
        }
    }

    /**
     * Display failure summary
     */
    displayFailureSummary(report) {
        console.log('\n💥 Update Failed:');
        console.log(`   Errors: ${report.statistics.errors}`);
        console.log(`   Warnings: ${report.statistics.warnings}`);
        console.log(`   Log: ${report.details.log_file}`);
        
        if (report.details.backup_path) {
            console.log(`   Backup available: ${report.details.backup_path}`);
        }
        
        if (report.details.errors.length > 0) {
            console.log('\n   Recent errors:');
            report.details.errors.slice(-3).forEach(error => {
                console.log(`   - ${error}`);
            });
        }
    }
}

// Command line interface
if (require.main === module) {
    const args = process.argv.slice(2);
    const options = {};
    
    // Parse command line arguments
    for (let i = 0; i < args.length; i++) {
        switch (args[i]) {
            case '--major':
                options.versionType = 'major';
                break;
            case '--minor':
                options.versionType = 'minor';
                break;
            case '--patch':
                options.versionType = 'patch';
                break;
            case '--skip-migrations':
                options.skipMigrations = true;
                break;
            case '--skip-optimization':
                options.skipOptimization = true;
                break;
            case '--skip-backup':
                options.skipBackup = true;
                break;
            case '--help':
                console.log(`
Friends of Children Ministries - Production Update Manager

Usage: node update-production.js [OPTIONS]

Options:
  --major              Increment major version (x.0.0)
  --minor              Increment minor version (x.y.0)
  --patch              Increment patch version (x.y.z) [default]
  --skip-migrations    Skip database migrations
  --skip-optimization  Skip Laravel optimization
  --skip-backup        Skip backup creation
  --help               Show this help message

Examples:
  node update-production.js                    # Patch update with full process
  node update-production.js --minor            # Minor version update
  node update-production.js --skip-migrations  # Skip database operations
`);
                process.exit(0);
        }
    }
    
    const updateManager = new ProductionUpdateManager();
    updateManager.executeUpdate(options)
        .then(exitCode => process.exit(exitCode))
        .catch(error => {
            console.error('Fatal error:', error.message);
            process.exit(1);
        });
}

module.exports = ProductionUpdateManager;