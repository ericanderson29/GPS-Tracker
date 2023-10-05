<?php declare(strict_types=1);

namespace App\Domains\Translation\Command;

class PlainExport extends CommandAbstract
{
    /**
     * @var string
     */
    protected $signature = 'translation:plain:export {--lang=}';

    /**
     * @var string
     */
    protected $description = 'Export translation file as JSON by {--lang=}';

    /**
     * @return void
     */
    public function handle()
    {
        $this->info('[START]');

        $this->checkOptions(['lang']);
        $this->requestWithOptions();

        $this->info($this->action()->plainExport());

        $this->info('[END]');
    }
}
