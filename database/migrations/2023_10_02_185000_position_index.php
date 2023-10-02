<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\CoreApp\Migration\MigrationAbstract;

return new class extends MigrationAbstract {
    /**
     * @return void
     */
    public function up(): void
    {
        $this->map();
        $this->tables();
        $this->upFinish();
    }

    /**
     * @return void
     */
    protected function map(): void
    {
        $this->db()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('point', 'array');
    }

    /**
     * @return void
     */
    protected function tables(): void
    {
        Schema::table('position', function (Blueprint $table) {
            $this->tableAddIndex($table, ['user_id', 'date_utc_at']);
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('position', function (Blueprint $table) {
            $this->tableDropIndex($table, ['user_id', 'date_utc_at']);
        });
    }
};
