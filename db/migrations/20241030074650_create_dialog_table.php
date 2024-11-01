<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateDialogTable extends AbstractMigration
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
        $table = $this->table('dialog');
        $table->addColumn('sender_id', 'integer', ['null' => true, 'signed' => false]);
        $table->addColumn('receiver_id', 'integer', ['null' => true, 'signed' => false]);
        $table->addColumn('created', 'datetime', ['null' => false]);

        $table->addForeignKey('sender_id', 'phpauth_users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION']);
        $table->addForeignKey('receiver_id', 'phpauth_users', 'id', ['delete'=> 'SET_NULL', 'update'=> 'NO_ACTION']);
        $table->create();
    }
}
