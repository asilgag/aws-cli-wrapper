# cli-wrapper

A simple PHP wrapper over CLI commands.

## Installation
    composer require asilgag/aws-cli-wrapper

## Usage

    use Asilgag\CliWrapper\AWS\AwsCliWrapper;
    use Asilgag\CliWrapper\CliCommand;
    
    // Create a new AWS CLI Wrapper.
    $awsCli = new AwsCliWrapper();
    
    // Set credentials if needed.
    $awsCli->setEnvironment('AWS_ACCESS_KEY_ID', '***');
    $awsCli->setEnvironment('AWS_SECRET_ACCESS_KEY', '***');
    
    // Set options for the "aws" part of the command.
    // @see https://docs.aws.amazon.com/cli/latest/reference/
    $awsCli->getAwsOptions()->add('--region us-east-1');
    
    // Set global options for specific commands.
    // In this example, all "s3" commands will be suffixed with "--only-show-errors".
    // @see https://docs.aws.amazon.com/cli/latest/reference/s3/
    $awsCli->globalOptions('s3')->add('--only-show-errors');

    // Create new command.
    $command = new CliCommand('s3', ['sync', '/source/path/', 's3://bucket/', '--include *.html', '--delete']);
    
    // Execute command. It will throw a RuntimeException if it exits with a non-zero code.
    try {
        $cli->exec($command);
    }
    catch (RuntimeException $e) {
        // Do some logging
    }
    
    
