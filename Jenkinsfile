node {
    def app

    stage("Clone repo") {
        checkout scm
    }

    stage("Install dependencies") {
        sh "composer install"
        sh "npm install"
        sh "npm run dev"
    }

    stage("Build image") {
        app = docker.build("ranbogmord/elm")
    }

    stage("Upload image to repository") {
        docker.withRegistry("https://registry.hub.docker.com", "docker-hub-creds") {
            app.push("${env.BUILD_NUMBER}")
            app.push("latest")
        }
    }
}