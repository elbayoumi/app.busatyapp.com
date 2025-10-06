const fs = require('fs');
const path = require('path');

// Service to save failed tracking batches locally
const LocalBackupService = {
    save(payload) {
        const backupDir = path.join(__dirname, '../backup');

        // Create backup directory if it doesn't exist
        if (!fs.existsSync(backupDir)) {
            fs.mkdirSync(backupDir);
        }

        const filePath = path.join(backupDir, `failed_batch_${Date.now()}.json`);

        fs.writeFileSync(filePath, JSON.stringify(payload, null, 2));

        console.log(`📦 Backup saved locally: ${filePath}`);
    }
};

module.exports = LocalBackupService;
