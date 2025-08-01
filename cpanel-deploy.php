<?php
/**
 * cPanel Deployment Script for Laravel Storage Symlink
 * 
 * This script should be run on your cPanel hosting after uploading your Laravel files
 * to create the storage symlink properly for your specific directory structure.
 */

// Define paths based on your cPanel structure
$publicPath = __DIR__ . '/public_html/app';  // Your Laravel public files location
$storagePath = __DIR__ . '/laravel/storage/app/public';  // Your Laravel storage location
$symlinkPath = $publicPath . '/storage';

echo "Laravel cPanel Storage Link Setup\n";
echo "==================================\n\n";

// Check if public directory exists
if (!is_dir($publicPath)) {
    echo "❌ Error: Public directory not found at: $publicPath\n";
    echo "Please ensure your Laravel public files are in public_html/app/\n";
    exit(1);
}

// Check if storage directory exists
if (!is_dir($storagePath)) {
    echo "❌ Error: Storage directory not found at: $storagePath\n";
    echo "Please ensure your Laravel files are in the laravel/ directory\n";
    exit(1);
}

// Remove existing symlink if it exists
if (file_exists($symlinkPath)) {
    if (is_link($symlinkPath)) {
        unlink($symlinkPath);
        echo "✅ Removed existing storage symlink\n";
    } else {
        echo "❌ Error: $symlinkPath exists but is not a symlink\n";
        echo "Please manually remove this file/directory\n";
        exit(1);
    }
}

// Create the symlink
if (symlink($storagePath, $symlinkPath)) {
    echo "✅ Storage symlink created successfully!\n";
    echo "   From: $symlinkPath\n";
    echo "   To: $storagePath\n\n";
    
    // Test the symlink
    if (is_readable($symlinkPath)) {
        echo "✅ Symlink is readable - storage should work correctly\n";
    } else {
        echo "⚠️  Warning: Symlink created but not readable - check permissions\n";
    }
    
    echo "\n📁 Your uploaded images will now be accessible at:\n";
    echo "   https://yourdomain.com/app/storage/[folder]/[filename]\n";
    
} else {
    echo "❌ Error: Failed to create storage symlink\n";
    echo "This might be due to:\n";
    echo "- Insufficient permissions\n";
    echo "- Server restrictions on symlink creation\n";
    echo "- Incorrect paths\n\n";
    echo "Manual alternative: Copy files instead of symlinking\n";
}

echo "\nDone!\n";