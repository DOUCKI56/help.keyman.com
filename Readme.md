# Development

## How to run help.keyman.com locally with Apache on Docker

Previously, the site was run in IIS, but is now migrated to Apache. The Docker image is configured to host the site that can be accessed at http://localhost:8055.


### Pre-requisites

On the host machine, install [Docker](https://docs.docker.com/get-docker/) and [Composer](https://getcomposer.org/download/):

On Windows, Docker will also need either:
* hyper-v or
* [WSL2](https://ubuntu.com/tutorials/install-ubuntu-on-wsl2-on-windows-10#1-overview)

### Builder actions

#### Configure
Install PHP dependencies in vendor/ folder
1. Run `./build.sh configure`.

#### Stop the Docker container
1. Run `./build.sh stop`

#### Build the Docker image
1. Run `./build.sh build`.

#### Start the Docker container
1. Run `./build.sh start`.

After this, you can access the help.keyman site at http://localhost:8055

#### Remove the Docker container and image
1. Run `./build.sh clean`.

#### Running tests
Checks for broken links
1. Run `./build.sh test`

## How to run help.keyman.com locally with Docker Desktop's Kubernetes singlenode cluster

For testing Kubernetes deployment there are yaml files under `resources/kubectl`, that cover local developer testing. 

### Pre-requisites
On the host machine, install [Docker](https://docs.docker.com/get-docker/), then enable Kubernetes in the settings.

Ensure you have built a help-keyman-app Docker image, and either tag it `docker.dallas.languagetechnology.org/keyman/help-keyman-app` or modify the `app-php` containers `image:` value to match you localy copies name.

To deploy the dev version to the cluster do the following:
1. Ensure your `kubectl` context is set to `docker-desktop`, though the Docker Desktop systray icon or running: 
```bash
$> kubectl config use-context docker-desktop
```
2. Create a keyman namespace if it does not already exist:
```bash
$> kubectl create ns keyman
```
3. Apply the configs for the resources and start the pod:
```bash
$> kubectl --namespace keyman apply \
       -f resources/kubectl/help-kubectl-dev.yaml \
       -f resources/kubectl/help-kubectl.yaml
```
The site can be reached on http://localhost:30080/ via web browser, and the deploy api is on http://localhost:30900/api/deploy, and can be activated like so:
```bash
$> curl -v --request POST \
    -H "Content-Type: application/json" \
    -H "X-Hub-Signature-256: sha256=49af8531106a369bfee369f91dadec597e8ea3992ec2802bbe655be0ece17f15" \
    --data '{"action":"push","ref":"refs/heads/staging"}' \
    http://localhost:30900/api/deploy
```
This simulates enough of GitHub webhook event, to pass validation on the responder.
4. Remove the k8s pod and resources, to delete everything do:
```bash
$> kubectl --namespace=keyman delete {pod,cm,svc,secret,pvc}/help-keyman-com
```
Or just delete the pod and keep the resources for further testing:
```bash
$> kubectl --namespace=keyman delete pod/help-keyman-com
```
