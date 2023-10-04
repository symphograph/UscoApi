# Команды терминала

```bash
# Обновить сайт из репозитория
cd && cd api.sakh-orch.ru && git pull
```
```bash
# Проверка конфигурации nginx
nginx -t
```
```bash
# Перзапуск nginx
systemctl restart nginx
```
```bash
# Проверка службы fpm 8.2
systemctl status php8.2-fpm
```

```bash
# Перезапуск fpm 8.2
systemctl restart php8.2-fpm
```