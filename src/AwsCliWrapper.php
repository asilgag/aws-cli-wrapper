<?php

namespace Asilgag\CliWrapper\AWS;

use Asilgag\CliWrapper\CliCommand;
use Asilgag\CliWrapper\CliOptionsBag;
use Asilgag\CliWrapper\CliWrapper;

/**
 * A simple PHP wrapper over AWS CLI.
 *
 * Given the following syntax:
 * aws [options] <command> <subcommand> [parameters]
 *   - "aws": already defined for you. No need to pass it as an option.
 *   - "[options]": use $this->getAwsOptions()->add($name, $value) to set them.
 *   - <command> <subcommand> [parameters]: pass a CliCommand to exec(), like
 *     new CliCommand('<command> <subcommand>', $parameters)
 */
class AwsCliWrapper extends CliWrapper {

  /**
   * A bag to hold global CLI options for the "aws" command.
   *
   * @var \Asilgag\CliWrapper\CliOptionsBag
   */
  protected $awsOptions;

  /**
   * AwsCliWrapper constructor.
   */
  public function __construct() {
    parent::__construct();
    $this->awsOptions = new CliOptionsBag();
  }

  /**
   * Get aws options bag.
   *
   * @return \Asilgag\CliWrapper\CliOptionsBag
   */
  public function getAwsOptions(): CliOptionsBag {
    return $this->awsOptions;
  }

  /*
   * {@inheritdoc}
   */
  public function exec(CliCommand $command, array &$output = NULL, &$return_var = NULL): void {
    // Ensure that AWS CLI (which runs on Python) access I/O with a proper
    // encoding, avoiding "'utf-8' codec can't encode character" and
    // "surrogates not allowed".
    $this->setEnvironment('PYTHONIOENCODING', 'utf-8');
    parent::exec($command, $output, $return_var);
  }

  /**
   * {@inheritdoc}
   */
  public function stringify(CliCommand $command): string {
    $commandParts[] = 'aws';
    if (count($this->awsOptions->getBag()) > 0) {
      $commandParts[] = implode(' ', $this->awsOptions->getBag());
    }
    $commandParts[] = $command->getCommand();
    if (count($command->getOptions()) > 0) {
      $commandParts[] = implode(' ', $command->getOptions());
    }
    return implode(' ', $commandParts);
  }

}
