<?php declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Shared\Migration\MigrationAbstract;

return new class extends MigrationAbstract
{
    /**
     * @return void
     */
    public function up()
    {
        $this->keys();
    }

    /**
     * @return void
     */
    protected function keys()
    {
        Schema::table('device_alarm_notification', function (Blueprint $table) {
            $this->tableDropForeign($table, 'device_alarm', 'fk_');
        });

        Schema::table('device_alarm_notification', function (Blueprint $table) {
            $table->unsignedBigInteger('device_alarm_id')->nullable(true)->change();
        });

        Schema::table('device_alarm_notification', function (Blueprint $table) {
            $this->foreignOnDeleteSetNull($table, 'device_alarm');
        });
    }

    /**
     * @return void
     */
    public function down()
    {
        Schema::table('device_alarm_notification', function (Blueprint $table) {
            $this->tableDropForeign($table, 'device_alarm', 'fk_');
        });

        Schema::table('device_alarm_notification', function (Blueprint $table) {
            $table->unsignedBigInteger('device_alarm_id')->nullable(false)->change();
        });

        Schema::table('device_alarm_notification', function (Blueprint $table) {
            $this->foreignOnDeleteCascade($table, 'device_alarm');
        });
    }
};
