<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAuthTables extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        // Config table
        $table = $this->table('phpauth_config', ['id' => false]);
        $table->addColumn('setting', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('value', 'string', ['limit' => 100, 'null' => true])
            ->addIndex(['setting'], ['unique' => true])
            ->create();

        $this->execute("
            INSERT INTO phpauth_config (setting, value) VALUES
            ('attack_mitigation_time', '+30 minutes'),
            ('attempts_before_ban', '30'),
            ('attempts_before_verify', '5'),
            ('bcrypt_cost', '10'),
            ('cookie_domain', NULL),
            ('cookie_forget', '+30 minutes'),
            ('cookie_http', '1'),
            ('cookie_name', 'phpauth_session_cookie'),
            ('cookie_path', '/'),
            ('cookie_remember', '+1 month'),
            ('cookie_samesite', 'Strict'),
            ('cookie_secure', '1'),
            ('cookie_renew', '+5 minutes'),
            ('allow_concurrent_sessions', FALSE),
            ('emailmessage_suppress_activation', '0'),
            ('emailmessage_suppress_reset', '0'),
            ('mail_charset', 'UTF-8'),
            ('password_min_score', '3'),
            ('site_activation_page', 'activate'),
            ('site_activation_page_append_code', '0'), 
            ('site_email', 'no-reply@phpauth.cuonic.com'),
            ('site_key', 'fghuior.)/!/jdUkd8s2!7HVHG7777ghg'),
            ('site_name', 'PHPAuth'),
            ('site_password_reset_page', 'reset'),
            ('site_password_reset_page_append_code', '0'),
            ('site_timezone', 'Europe/Paris'),
            ('site_url', 'https://github.com/PHPAuth/PHPAuth'),
            ('site_language', 'en_GB'),
            ('smtp', '0'),
            ('smtp_debug', '0'),
            ('smtp_auth', '1'),
            ('smtp_host', 'smtp.example.com'),
            ('smtp_password', 'password'),
            ('smtp_port', '25'),
            ('smtp_security', NULL),
            ('smtp_username', 'email@example.com'),
            ('table_attempts', 'phpauth_attempts'),
            ('table_requests', 'phpauth_requests'),
            ('table_sessions', 'phpauth_sessions'),
            ('table_users', 'phpauth_users'),
            ('table_emails_banned', 'phpauth_emails_banned'),
            ('table_translations', 'phpauth_translation_dictionary'),
            ('verify_email_max_length', '100'),
            ('verify_email_min_length', '5'),
            ('verify_email_use_banlist', '1'),
            ('verify_password_min_length', '3'),
            ('request_key_expiration', '+10 minutes'),
            ('translation_source', 'php'),
            ('recaptcha_enabled', 0),
            ('recaptcha_site_key', ''),
            ('recaptcha_secret_key', ''),
            ('custom_datetime_format', 'Y-m-d H:i'),
            ('uses_session', 0)
            ON DUPLICATE KEY UPDATE
            value = VALUES(value);
        ");


        // Attempts table
        $table = $this->table('phpauth_attempts');
        $table->addColumn('ip', 'string', ['limit' => 39, 'null' => false])
            ->addColumn('expiredate', 'datetime', ['null' => false])
            ->addIndex(['ip'])
            ->create();

        // Requests table
        $table = $this->table('phpauth_requests');
        $table->addColumn('uid', 'integer', ['null' => false])
            ->addColumn('token', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('expire', 'datetime', ['null' => false])
            ->addColumn('type', 'enum', ['values' => ['activation', 'reset'], 'null' => false])
            ->addIndex(['uid'])
            ->addIndex(['token'])
            ->addIndex(['type'])
            ->create();

        // Sessions table
        $table = $this->table('phpauth_sessions');
        $table->addColumn('uid', 'integer', ['null' => false])
            ->addColumn('hash', 'string', ['limit' => 40, 'null' => false])
            ->addColumn('expiredate', 'datetime', ['null' => false])
            ->addColumn('ip', 'string', ['limit' => 39, 'null' => false])
            ->addColumn('device_id', 'string', ['limit' => 36, 'null' => true])
            ->addColumn('agent', 'string', ['limit' => 200, 'null' => false])
            ->addColumn('cookie_crc', 'string', ['limit' => 40, 'null' => false])
            ->create();

        // Users table
        $table = $this->table('phpauth_users');
        $table->addColumn('email', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('password', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('name', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('surname', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('isactive', 'boolean', ['default' => false])
            ->addColumn('dt', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['email'])
            ->create();

        // Banned emails table
        $table = $this->table('phpauth_emails_banned');
        $table->addColumn('domain', 'string', ['limit' => 100, 'null' => true])
            ->create();
    }
}
