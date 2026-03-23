pipeline {
    agent {
        docker {
            image 'cfarm-php:8.4'
            args '-u root'
        }
    }

    stages {

        stage('Checkout') {
            steps {
                echo 'Pulling latest code...'
                checkout scm
                sh 'git config --global --add safe.directory /var/jenkins_home/workspace/cFarm'
            }
        }

        stage('Install PHP Dependencies') {
            steps {
                echo 'Installing Composer dependencies...'
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }

        stage('Install Node Dependencies') {
            steps {
                echo 'Installing npm dependencies...'
                sh 'npm ci'
            }
        }

        stage('Build Assets') {
            steps {
                echo 'Building frontend assets...'
                sh 'npm run build'
            }
        }

        stage('Prepare Environment') {
            steps {
                echo 'Setting up test environment...'
                sh '''
                    cp .env.testing.example .env.testing
                    php artisan key:generate --env=testing
                '''
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
