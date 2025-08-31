SOCCER SOCIETY
---------------------------        
Monte partidas de futebol society e organize Jogadores por Posiçoes e XP em time rivais.

Disponivel no link abaixo:
http://34.31.155.121:8080/

ALGORITMO DE BALANCEAMENTO
----------------------------------------
**Algoritmo de Balanceamento de Times**

O algoritmo de balanceamento de times utiliza uma abordagem ávida (Greedy) com restrições de posições e distribuição de experiência. Ele é projetado para montar dois times equilibrados a partir de uma lista de jogadores, considerando tanto a posição quanto o nível de XP de cada um.

**Organização dos Jogadores**

Os jogadores são inicialmente separados pelas posições críticas, como goleiros, garantindo que cada time receba pelo menos um deles. Os demais jogadores são organizados em uma árvore binária de busca (BST) baseada no XP. Essa estrutura permite que os jogadores sejam ordenados de forma eficiente, do maior para o menor XP, usando uma varredura em ordem reversa (reverse in-order).

**Composição mínima**

Antes de distribuir os jogadores restantes, o algoritmo assegura que cada time tenha as posições essenciais preenchidas. Por exemplo, cada time recebe pelo menos um goleiro, evitando que um time fique sem essa posição estratégica.

**Distribuição balanceada**

Depois da composição mínima, os jogadores restantes são alocados alternadamente entre os dois times, levando em consideração dois fatores principais:

Número de jogadores em cada time – evitando que um time fique maior que o outro.

XP total do time – o jogador é atribuído ao time que possui menos XP acumulado, garantindo equilíbrio na experiência geral.

Essa abordagem garante que a diferença de força entre os times seja minimizada, mantendo a competição justa.

Respeito ao tamanho dos times

O algoritmo calcula automaticamente o número máximo de jogadores por time, considerando o total de participantes. Caso haja jogadores insuficientes para formar dois times completos, apenas um time é gerado. Caso contrário, dois times são formados com a mesma quantidade de jogadores.

Algoritmo Greedy (Ávido) com Árvores
--------------------------------------------------------------

O núcleo do algoritmo segue a lógica de um Greedy (Ávido):

Escolha local ótima: a cada passo, o jogador é colocado no time que mais precisa de XP ou que ainda não atingiu seu limite de tamanho.

Solução incremental: cada decisão é feita sem reconsiderar as escolhas anteriores, mas o resultado final tende a ser equilibrado.

Eficiência: combinando o Greedy com a BST, o algoritmo consegue ordenar e distribuir jogadores rapidamente, mesmo com listas grandes, mantendo a simplicidade do código e reduzindo a chance de erros.

Setup Docker Laravel 11 com PHP 8.3
------------------------------------------------------------
Clone o Repositorio

        git@github.com:andamorim2206/soccer-society.git
Instala o . env

        cp .env.example .env
Suba os container

        docker-compose up -d --build
Acessar o container

        docker-compose exec laravel_app bash
Instalar as Dependencias(dentro container)

        composer install

Chave do Laravel(ainda dentro do container)

        php artisan key:generate
Rodar a migrate e seeder

        php artisan migrate
        php artisan db:seed

Apos isso saia do container e acesse:

        localhost:8080 (aplicação)
        localhost8081 (phpmyadmin)



