kubectl create -f kube/postgres-pv-claim.yaml
kubectl create -f kube/postgres.yaml 
kubectl create -f kube/postgres-svc.yaml 

kubectl create -f kube/redis.yaml
kubectl create -f kube/redis-svc.yaml 

kubectl create -f kube/webserver.yaml
kubectl create -f kube/webserver-svc.yaml 

