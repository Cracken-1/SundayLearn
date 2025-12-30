{pkgs}: {
  channel = "stable-24.05";
  packages = [
    pkgs.nodejs_20
    pkgs.npm
  ];
  idx.extensions = [
    "svelte.svelte-vscode"
    "vue.volar"
  ];
  idx.previews = {
    previews = {
      web = {
        command = [
          "php"
          "-S"
          "0.0.0.0:8080"
          "-t"
          "public"
        ];
        manager = "web";
      };
    };
  };
}
