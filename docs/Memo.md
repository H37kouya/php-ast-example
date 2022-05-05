# Memo

## Docker

- [Dockerのコンテナを起動したままにする](https://qiita.com/reflet/items/dd65f9ffef2a2fcfcf30)

docker-composeで「tty:true」とすると起動しっぱなしになる

```docker-compose.yml
version: '2'
services:
  java:
    image: java:8
    container_name: java
    tty: true
```
