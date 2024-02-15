<?php

namespace Botble\AuditLog\Commands;

use Botble\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Illuminate\Console\Command;
use Throwable;

class CleanOldLogsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:activity-logs:clean-old-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean logs over 30 days';

    /**
     * @var AuditLogInterface
     */
    protected $auditLogRepository;

    /**
     * RebuildPermissions constructor.
     *
     * @param AuditLogInterface $auditLogRepository
     */
    public function __construct(AuditLogInterface $auditLogRepository)
    {
        parent::__construct();
        $this->auditLogRepository = $auditLogRepository;
    }

    /**
     * Execute the console command.
     *
     * @throws Throwable
     */
    public function handle()
    {
        $this->info('Processing...');
        $this->auditLogRepository->getModel()->whereDate('created_at', '<', now()->days(30)->toDateString())->delete();
        $this->info('Done!');
    }
}
