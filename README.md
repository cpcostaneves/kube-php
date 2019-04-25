# kube-php

Kubernetes dev env for PHPP/Apache, Postgresql, Redis.

## Requirements

Requirements:
- docker
- minikube
- kubectl
- virtualization
- admin previledges

### Docker

For Linux use package manager and install docker package
For Windows follow: https://docs.docker.com/docker-for-windows/install/

### Minikube and kubectl

Follow installation steps:
- https://kubernetes.io/docs/tasks/tools/install-kubectl/
- https://kubernetes.io/docs/tasks/tools/install-minikube/


Recommended using latest static binaries.

### Virtualization

Minikube requires virtualization. VT-x/AMD-v virtualization must be enabled in BIOS. Also, a hypervisor is required:
- Recommended for Windows: VirtualBox. Download and install from: https://www.virtualbox.org/wiki/Downloads
- Recommended for Linux: KVM2

For Linux KVM2 setup:

Install libvirt and qemu-kvm on your system, e.g.
- Debian/Ubuntu
```
$ sudo apt install libvirt-bin qemu-kvm
```

- Fedora/CentOS/RHEL
```
$ sudo yum install libvirt-daemon-kvm kvm
```

Add yourself to the libvirtd group

- Debian/Ubuntu (NOTE: For Ubuntu 17.04 change the group to `libvirt`)
```
$ sudo usermod -a -G libvirtd $(whoami)
$ newgrp libvirtd
```

- Fedora/CentOS/RHEL
```
$ sudo usermod -a -G libvirt $(whoami)
$ newgrp libvirt
```

Enable and Start
```
$ sudo systemctl start libvirtd
$ sudo systemctl enable libvirtd
```

Minikube KVM driver installation
```
$ curl -LO https://storage.googleapis.com/minikube/releases/latest/docker-machine-driver-kvm2 \
    && sudo install docker-machine-driver-kvm2 /usr/local/bin/ && rm docker-machine-driver-kvm2
```

## Start minikube

Start minikube, select vm driver and mount dev directory

Linux
```
minikube start --vm-driver kvm2 --mount-string "$pwd:/datausr" --mount
```

Windows
```
minikube start --mount-string "c:\temp\kube-php:/datausr" --mount
```

Notes:
- View dashboard
```
minikube dashboard
```
- Delete cluster after use:
```
minikube delete
```

## Access minikube docker.

- Linux
```
eval $(minikube docker-env)
```

- Windows
```
@FOR /f "tokens=*" %i IN ('minikube docker-env') DO @%i
```

Note: To undo:
```
eval $(minikube docker-env -u)
```


## Build PHP docker image

Build PHP/Apache docker image with support for Redis and Postgresql.

```
docker build -t webserver:latest .
```


## Deployments and Services

- Linux
```
chmod 775 deploy.sh
./deploy.sh
```

- Windows
```
deploy.cmd
``` 

Wait for the deployment. Check status with:
``` 
kubectl get all
``` 

Notes:
- Using 1 pod per service. To run more, change 'replicas' field inside deploy files in kube/ dir.
- if you are using another mount point (other than /data/usr), change last path field in kube/webserver.yaml file. After, create or re-apply deployment.


## Access Web Server


Map service port to localhost:
``` 
kubectl port-forward svc/webserver 8080:80 &
``` 

Init and populate Database (if you don't have cURL, use a web browser):
``` 
curl http://127.0.0.1:8080/init.php
``` 

Open using web browser:
- http://127.0.0.1:8080/


Other options to open web server page:
Get service URL:
``` 
minikube service webserver --url
``` 
Or open browser:
``` 
minikube service webserver
``` 

## Development

Edit sources in ./src and test with a web browser.

## Other commands

- Get all cluster info:
``` 
kubectl get all
``` 

- Service list:
``` 
minikube service list
``` 

- Update deploy or service:
``` 
kubectl apply -f webserver.yaml
``` 

- Describe pods from deploy:
``` 
kubectl describe pods postgres
``` 

- Shell inside minikube VM:
``` 
minikube ssh
``` 

- Run commands in a pod
``` 
kubectl exec -it <PODNAME> -- /bin/bash
``` 


## Docker compose option

In the docker-compose  directory there is a similar solution using docker-compose (required). To run it:
```
docker-compose up
``` 

Init and populate Database (if you don't have cURL, use a web browser):
``` 
curl http://127.0.0.1:8000/init.php
``` 

Open using web browser:
- http://127.0.0.1:8000/


## Improvements

Possible improvements:
- Check Redis cluster settings (master and replicas communication).
- Use nginx as a web gateway.
- Use env vars to pass secrets to webserver deploy (pass postgres and redis config).
- Improve index.php.
