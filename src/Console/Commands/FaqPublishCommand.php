<?php

namespace Crow\Faq\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Crow\Faq\Providers\FaqProvider;

class FaqPublishCommand extends Command
{
    protected $signature = 'faq:publish {--tag=* : Tag for published}';
    protected $description = 'Publish files for faq package';
    private array $files = [];
    private array $fileTags = [
        'config',
        'migration',
        'lang',
    ];

    public function handle(): void
    {
        $option = is_array($this->option('tag')) && !empty($this->option('tag')) ? $this->option('tag')[0] : '';

        $this->parsePublishedFiles();

        switch ($option) {
            case 'config':
                $this->copyConfig();
                break;
            case 'migration':
                $this->copyMigration();
                $this->copyMigrationRelation();
                break;
            default:
                $this->error('Not selected tag');
                break;
        }
    }

    private function parsePublishedFiles(): void
    {
        $index = 0;
        foreach (FaqProvider::pathsToPublish(FaqProvider::class) as $k => $v) {
            $this->files[$this->fileTags[$index]] = [
                'from' => $k,
                'to' => $v,
            ];
            $index++;
        }
    }

    private function copyConfig(): void
    {
        $this->copyFiles($this->files['config']['from'], $this->files['config']['to']);
    }

    private function copyMigration(): void
    {
        $filename = sprintf(
            '%s_create_%s.php',
            now()->format('Y_m_d_His'),
            Config::get('faq.table.category')
        );

        $this->copyFile(
            $this->files['migration']['from'] . DIRECTORY_SEPARATOR . 'faq_categories.stub',
            $this->files['migration']['to'] . DIRECTORY_SEPARATOR . $filename,
            Config::get('faq.table.category')
        );

        $filename = sprintf(
            '%s_create_%s.php',
            now()->format('Y_m_d_His'),
            Config::get('faq.table.faq')
        );

        $this->copyFile(
            $this->files['migration']['from'] . DIRECTORY_SEPARATOR . 'faqs.stub',
            $this->files['migration']['to'] . DIRECTORY_SEPARATOR . $filename,
            Config::get('faq.table.faq')
        );
    }

    private function copyMigrationRelation(): void
    {
        $filename = sprintf(
            '%s_create_%s.php',
            now()->format('Y_m_d_His'),
            Config::get('faq.table.category_relation')
        );

        $this->copyFile(
            $this->files['migration']['from'] . DIRECTORY_SEPARATOR . 'faq_category_relations.stub',
            $this->files['migration']['to'] . DIRECTORY_SEPARATOR . $filename,
            Config::get('faq.table.category_relation')
        );

        $filename = sprintf(
            '%s_create_%s.php',
            now()->format('Y_m_d_His'),
            Config::get('faq.table.faq_relation')
        );

        $this->copyFile(
            $this->files['migration']['from'] . DIRECTORY_SEPARATOR . 'faq_relations.stub',
            $this->files['migration']['to'] . DIRECTORY_SEPARATOR . $filename,
            Config::get('faq.table.faq_relation')
        );
    }

    private function copyFiles(string $from, string $to): void
    {
        if (!file_exists($to)) {
            mkdir($to, 0755, true);
        }
        $from = rtrim($from, '/') . '/';
        $to = rtrim($to, '/') . '/';
        foreach (scandir($from) as $file) {
            if (!is_file($from . $file)) {
                continue;
            }

            $path = strtr(
                $to . $file,
                [
                    base_path() => ''
                ]
            );

            if (file_exists($to . $file)) {
                $this->info(
                    sprintf(
                        'File "%s" skipped',
                        $path
                    )
                );
                continue;
            }

            $this->copyFile($from . $file, $to . $file);
        }
    }

    private function copyFile(string $from, string $to, ?string $table = null): void
    {
        copy(
            $from,
            $to
        );

        $content = file_get_contents($to);
        $content = strtr($content, [
            '{{TABLE_NAME}}' => $table,
            '{{MIGRATION_CLASS_NAME}}' => Str::ucfirst(Str::camel($table)),
        ]);
        file_put_contents($to, $content);

        $path = strtr(
            $to,
            [
                base_path() => ''
            ]
        );

        $this->info(
            sprintf(
                'File "%s" copied',
                $path
            )
        );
    }
}
