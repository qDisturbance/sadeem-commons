<?php


namespace Sadeem\Commons\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishConfig extends Command
{
  protected $signature = 'sadeem:config';

  protected $description = 'publish or overwrite configuration';

  public function handle()
  {
    $this->info('Installing Sadeem Commons...');

    $this->info('Publishing configuration...');

    if (! $this->configExists('sadeem.php')) {
      $this->publishConfiguration();
      $this->info('Published configuration');
    } else {
      if ($this->shouldOverwriteConfig()) {
        $this->info('Overwriting configuration file...');
        $this->publishConfiguration($force = true);
      } else {
        $this->info('Existing configuration was not overwritten');
      }
    }
  }

  private function configExists($fileName)
  {
    return File::exists(config_path($fileName));
  }

  private function shouldOverwriteConfig()
  {
    return $this->confirm(
      'Config file already exists. Do you want to overwrite it?',
      false
    );
  }

  private function publishConfiguration($forcePublish = false)
  {
    $params = [
      '--provider' => "Sadeem\Commons\SadeemServiceProvider",
      '--tag' => "config"
    ];

    if ($forcePublish === true) {
      $params['--force'] = '';
    }

    $this->call('vendor:publish', $params);
  }
//
//
}
