@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../hightman/xunsearch/util/xs
php "%BIN_TARGET%" %*
