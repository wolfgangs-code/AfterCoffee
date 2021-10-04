#!/bin/bash
echo "AfterCoffee Release Builder"
read -p "=What is the release tag? >" tag
mkdir buildtmp

folders=("auth" "config" "editor" "lib" "pages" "plugins" "resource" "src" "widget")
folders+=("index.php" "LICENSE")

for f in "${folders[@]}"
do
	cp -r $f buildtmp
done

# Clean directories
find buildtmp/pages -mindepth 1 -not -name 'default-index.md' -delete
find buildtmp/plugins -type f -not -name 'customMarkdown.php'  -not -name 'dateFormat.php' -not -name 'directoryList.php' -delete
find buildtmp -type f -name '*.gitkeep' -delete
rm buildtmp/auth/auth_code.php

# Build ZIP
cd buildtmp
zip -r "../AfterCoffee-"$tag".zip" ./* -q
cd ..
rm -rf buildtmp
echo "Release $tag built!"