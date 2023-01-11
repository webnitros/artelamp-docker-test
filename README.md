MODX Docker Project by Pixmill
---

Клонировать сборку:

```
git clone https://github.com/fandeco/artelamp-docker.git
```

### Prepare Docker

If you have no Docker, please install it with

```
brew install docker --cask
brew install docker-compose
```

### Запустить docker

If you have no Docker, please install it with

```
cd docker
make docker-start
```

### Импорт mysql

Взять файл из директории:

```
make modx-restore-install
```



```yaml
https://ivan-shamaev.ru/docker-compose-tutorial-container-image-install/#__Linux_Install_Docker_on_Ubuntu_2004
```
