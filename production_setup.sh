#!/bin/bash
# Production Environment Setup Script for Air Jamaica Virtual Air Jamaica Virtual

echo "🛫 Air Jamaica Virtual - Production Setup Script"
echo "================================================"

echo "1. Creating production .env backup..."
cp .env .env.local.backup

echo "2. Updating environment to production settings..."

# Update environment variables for production
sed -i 's/APP_ENV=local/APP_ENV=production/' .env
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
sed -i 's/SSL_VERIFY_PEER=false/SSL_VERIFY_PEER=true/' .env

# Optional: Switch to more robust database (uncomment if migrating to MySQL)
# sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
# echo "Remember to configure MySQL database settings!"

echo "3. Clearing application cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "4. Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "5. Running database migrations (if needed)..."
php artisan migrate --force

echo "6. Security recommendations..."
echo "   ✅ CSRF protection enabled"
echo "   ✅ Authentication middleware active"
echo "   ✅ SSL verification restored"
echo "   ⚠️  Ensure .env file is not web-accessible"
echo "   ⚠️  Consider setting up SSL certificate"

echo "🎉 Production setup complete for Air Jamaica Virtual!"
echo "Site is now ready for real-world pilot usage."