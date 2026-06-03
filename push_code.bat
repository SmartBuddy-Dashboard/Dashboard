@echo off
echo ==================================================
echo Pushing SmartBuddy Dashboard to GitHub
echo ==================================================
cd /d "%~dp0"

echo [1/5] Initializing Git repository...
git init

echo [2/5] Adding all files...
git add .

echo [3/5] Committing changes...
git commit -m "Initial commit of SmartBuddy Dashboard with UI improvements"

echo [4/5] Setting up remote repository...
git remote add origin https://github.com/SmartBuddy-Dashboard/Dashboard.git
git branch -M main

echo [5/5] Pushing to GitHub (A browser window may open for authentication)...
git push -u origin main

echo ==================================================
if %errorlevel% neq 0 (
    echo ERROR: Failed to push to GitHub.
) else (
    echo SUCCESS: Code successfully pushed to GitHub!
)
echo ==================================================
pause
