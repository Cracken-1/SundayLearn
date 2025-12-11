<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Manager - Sunday School Platform</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        .warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            color: #856404;
        }
        .warning strong { display: block; margin-bottom: 5px; }
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background: none;
            color: #666;
            font-size: 14px;
            font-weight: 500;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
        }
        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            width: 100%;
        }
        button:hover {
            background: #5568d3;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .hash-output {
            background: #f8f9fa;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            word-break: break-all;
            font-family: monospace;
            font-size: 12px;
            margin-top: 10px;
        }
        .sql-output {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin-top: 10px;
            overflow-x: auto;
        }
        .help-text {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="warning">
            <strong>⚠️ SECURITY WARNING</strong>
            Delete this file (admin-user-manager.php) after creating your admin users!
        </div>

        <h1>🔐 Admin User Manager</h1>
        <p class="subtitle">Create and manage admin users for your Sunday School platform</p>

        <div class="tabs">
            <button class="tab active" onclick="showTab('generate')">Generate Password</button>
            <button class="tab" onclick="showTab('create')">Create User SQL</button>
            <button class="tab" onclick="showTab('update')">Update Password SQL</button>
        </div>

        <!-- Generate Password Tab -->
        <div id="generate" class="tab-content active">
            <form method="POST">
                <input type="hidden" name="action" value="generate">
                <div class="form-group">
                    <label for="password">Enter Password</label>
                    <input type="password" id="password" name="password" required 
                           placeholder="Enter a strong password">
                    <div class="help-text">Minimum 8 characters recommended</div>
                </div>
                <button type="submit">Generate Password Hash</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'generate') {
                $password = $_POST['password'] ?? '';
                if ($password) {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    echo '<div class="success">Password hash generated successfully!</div>';
                    echo '<div class="hash-output">' . htmlspecialchars($hash) . '</div>';
                    echo '<div class="help-text" style="margin-top: 10px;">Copy this hash and use it in the SQL queries below or in phpMyAdmin</div>';
                }
            }
            ?>
        </div>

        <!-- Create User Tab -->
        <div id="create" class="tab-content">
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="john@example.com">
                </div>
                <div class="form-group">
                    <label for="create_password">Password</label>
                    <input type="password" id="create_password" name="create_password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="super_admin">Super Admin (Full Access)</option>
                        <option value="admin">Admin (Most Access)</option>
                        <option value="editor">Editor (Limited Access)</option>
                    </select>
                </div>
                <button type="submit">Generate SQL Query</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'create') {
                $name = $_POST['name'] ?? '';
                $email = $_POST['email'] ?? '';
                $create_password = $_POST['create_password'] ?? '';
                $role = $_POST['role'] ?? 'admin';
                
                if ($name && $email && $create_password) {
                    $hash = password_hash($create_password, PASSWORD_BCRYPT);
                    $sql = "INSERT INTO `admin_users` (`name`, `email`, `password`, `role`, `is_active`, `created_at`, `updated_at`) \nVALUES (\n  '" . addslashes($name) . "',\n  '" . addslashes($email) . "',\n  '" . $hash . "',\n  '" . $role . "',\n  1,\n  NOW(),\n  NOW()\n);";
                    
                    echo '<div class="success">SQL query generated! Copy and run this in phpMyAdmin:</div>';
                    echo '<div class="sql-output">' . htmlspecialchars($sql) . '</div>';
                    echo '<div class="help-text" style="margin-top: 10px;">
                        <strong>How to use:</strong><br>
                        1. Login to phpMyAdmin in InfinityFree<br>
                        2. Select your database<br>
                        3. Click "SQL" tab<br>
                        4. Paste this query and click "Go"
                    </div>';
                }
            }
            ?>
        </div>

        <!-- Update Password Tab -->
        <div id="update" class="tab-content">
            <form method="POST">
                <input type="hidden" name="action" value="update">
                <div class="form-group">
                    <label for="update_email">User Email</label>
                    <input type="email" id="update_email" name="update_email" required 
                           placeholder="admin@example.com">
                    <div class="help-text">Email of the user whose password you want to change</div>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <button type="submit">Generate Update SQL</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'update') {
                $update_email = $_POST['update_email'] ?? '';
                $new_password = $_POST['new_password'] ?? '';
                
                if ($update_email && $new_password) {
                    $hash = password_hash($new_password, PASSWORD_BCRYPT);
                    $sql = "UPDATE `admin_users` \nSET `password` = '" . $hash . "',\n    `updated_at` = NOW()\nWHERE `email` = '" . addslashes($update_email) . "';";
                    
                    echo '<div class="success">SQL query generated! Copy and run this in phpMyAdmin:</div>';
                    echo '<div class="sql-output">' . htmlspecialchars($sql) . '</div>';
                    echo '<div class="help-text" style="margin-top: 10px;">
                        <strong>How to use:</strong><br>
                        1. Login to phpMyAdmin in InfinityFree<br>
                        2. Select your database<br>
                        3. Click "SQL" tab<br>
                        4. Paste this query and click "Go"
                    </div>';
                }
            }
            ?>
        </div>

        <div class="footer">
            <strong>Remember:</strong> Delete this file after creating your admin users!<br>
            Sunday School Platform © <?php echo date('Y'); ?>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
