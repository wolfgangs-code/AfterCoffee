#!/bin/bash
echo "AfterCoffee Release Builder"
read -p "=What is the release tag? >" tag
mkdir buildtmp

folders=("auth" "config" "docs" "editor" "lib" "pages" "plugins" "resource" "src" "widget")
folders+=("index.php" "LICENSE")

for f in "${folders[@]}"
do
	cp -r $f buildtmp
done

# Flag as release build
sed -i -e "s/RELEASE = false/RELEASE = true/g" ./src/Globals.php

# Clean directories
find buildtmp/pages -mindepth 1 -not -name 'default-index.md' -delete
find buildtmp/plugins -type f -not -name 'customMarkdown.php'  -not -name 'dateFormat.php' -not -name 'directoryList.php' -delete
find buildtmp -type f -name '*.gitkeep' -delete
rm buildtmp/auth/auth_code.php

# Build ZIP
cd buildtmp
zip -r "../AfterCoffee-"$tag".zip" ./* -q
cd ..
sed -i -e "s/RELEASE = true/RELEASE = false/g" ./src/Globals.php
rm -rf buildtmp
echo "Release $tag built!"