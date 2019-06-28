# Skeleton para projetos novos

Esse é um projeto para ser usado como bootstrap de novos projetos.

## Requisitos

- PHP 7.1 ou maior (Foi desenvolvimento no PHP 7.3)
- Angular 7
- Node (Preferencialmente 10)

## Tutorial

Para "instalar" esse projetos, siga os passos abaixo:

- 1 git clone https://github.com/joaowebdec/base_angular_lumen.git
2 - cd base_angular_lumnen
3 - mv front/ ../base_angular
4 - cd base_angular
5 - npm install
6 - ng serve --open (Se já tem alguma aplicação na porta 4200 adicione --port 4300 ou qualquer outra porta)  
7 - Aguarda a aplicação (Front) abrir no browser, abrindo sua aplicação angular está rodando, próximo passo é a api
8 - cd ../base_angular_lumen
9 - composer install
10 - Crie o banco de dados 
11 - Dentro da pasta base_angular_lumen, copie o arquivo .env.example e renomeio para somente .env, após isso modifique o .env com os dados de conexão do banco
12 - php artisan migrate (Para criar as tabelas)
13 - php artisan db:seed para popular a(s) tabela(s)
14 - No arquivo "UsersTableSeeder" tem os 2 usuários default com suas senhas, utiliza para acessar a plataforma e enjoy

\o/
