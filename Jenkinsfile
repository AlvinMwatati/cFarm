pipeline {
    agent any

    environment {
        PHP_VERSION = '8.3'
    }

    stages {

        stage('Checkout') {
            steps {
                echo 'Pulling latest code from GitHub...'
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                echo 'Installing Composer dependencies...'
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }

        stage('Prepare Environment') {
            steps {
                echo 'Setting up .env for testing...'
                sh 'cp .env.example .env.testing'
                sh 'php artisan key:generate --env=testing'
            }
        }

        stage('Run Tests') {
            steps {
                echo 'Running PHPUnit tests...'
                sh 'php artisan test --env=testing'
            }
        }

    }

    post {
        success {
            echo '✅ All tests passed!'
        }
        failure {
            echo '❌ Tests failed. Check the logs above.'
        }
        always {
            echo 'Pipeline finished.'
        }
    }
}
