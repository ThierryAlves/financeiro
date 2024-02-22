## Projeto de API REST financeira

### Criando o env
```
mv php/.env.example php/.env
```

### Como iniciar o projeto
```
docker-compose up
```

Assim que o container estiver o rodando, é necessário executar alguns comandos para preparar o ambiente


### Cria as tabelas do banco de dados
```
docker exec financeiro-financeiro-1 php artisan migrate --force
```
### Popula as tabelas base
```
docker exec financeiro-financeiro-1 php artisan db:seed
```
### Gera a documentação
```
docker exec financeiro-financeiro-1 php artisan l5-swagger:generate
```

## Testes

Esse projeto possui testes unitários e de feature/integração.  
Para executar os testes, pode ser utilizado o comando abaixo com o container rodando

```
docker exec financeiro-financeiro-1 php artisan test
```
