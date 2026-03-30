pipeline {
    agent {
        docker {
            image 'cfarm-php:8.4'
            args '-u root'
        }
    }

    tools {
        // This ensures 'npm' is added to the PATH for this build
        nodejs "NodeJS-20"
    }

    // Environment variables can be defined here if needed
    stages {

        stage('Checkout') {
            steps {
                deleteDir()
                echo 'Pulling latest code...'
                checkout scm
                sh 'git config --global --add safe.directory /var/jenkins_home/workspace/cFarm'
            }
        }

        // This stage is optional but can help verify the workspace contents
        stage('Validate') {
            steps {
                script {
                    sh 'ls -la'
                }
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
