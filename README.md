SOCCER SOCIETY
---------------------------        
Monte partidas de futebol society e organize Jogadores por Posiçoes e XP em time rivais.

Disponivel no link abaixo:
http://34.31.155.121:8080/

Balanceamento de Times - Explicação Teórica
-----------------------------------------------------------
O TeamBalancer é uma classe responsável por organizar jogadores em dois times e um time reserva (bench), respeitando posições e experiência de cada jogador. O objetivo é distribuir os jogadores de forma equilibrada, garantindo que ambos os times fiquem competitivos, enquanto jogadores excedentes ficam no banco.

O algoritmo utilizado é uma estratégia greedy (gulosa), que funciona da seguinte forma:

Agrupamento por posição:
Primeiro, todos os jogadores são agrupados de acordo com suas posições: Goleiro, Zagueiro, Meio-campo e Atacante. Dentro de cada grupo, os jogadores são ordenados pela experiência (XP) em ordem decrescente, garantindo que os jogadores mais experientes sejam distribuídos primeiro.

Distribuição inicial (setup):
Cada posição é processada separadamente.

Posições “ranged” (Atacante e Meio-campo): são distribuídas alternadamente entre os dois times, de forma a balancear a força ofensiva e o controle de meio-campo.

Posições fixas (Goleiro e Zagueiro): também são distribuídas alternadamente, garantindo que cada time tenha pelo menos um goleiro e uma base defensiva equilibrada.

Essa fase garante que os elementos-chave de cada time sejam atribuídos primeiro.

Distribuição dos jogadores restantes:
Após a distribuição inicial, os jogadores que não foram alocados são colocados nos times ainda com vagas disponíveis. Aqui, a estratégia greedy é clara: o algoritmo pega o próximo jogador disponível e o coloca no time que ainda não atingiu o limite de jogadores por time, sem tentar otimizar globalmente o balanceamento, apenas preenchendo as lacunas restantes.

Determinação do banco de reservas (bench):
Todos os jogadores que não couberam nos times principais são enviados para o time reserva, garantindo que haja suplentes para substituições futuras.

Por que é considerado um algoritmo greedy?

O método é guloso porque toma decisões locais ótimas em cada passo:

Primeiro, pega os jogadores mais experientes de cada posição e os distribui alternadamente entre os times.

Depois, preenche os times com os jogadores restantes na ordem que aparecem, sem reavaliar ou reorganizar globalmente.

Essa abordagem é simples, eficiente e rápida, ideal para cenários em que a prioridade é rapidez e simplicidade de balanceamento, mesmo que não produza uma solução perfeita para todos os cenários possíveis.

Setup Docker Laravel 11 com PHP 8.3
------------------------------------------------------------
Clone o Repositorio

        git@github.com:andamorim2206/soccer-society.git
Instala o . env

        cp .env.example .env
Suba os container

        docker-compose up -d --build
Acessar o container

        docker exec -it laravel_app bash
Instalar as Dependencias(dentro container)

        composer install

Chave do Laravel(ainda dentro do container)

        php artisan key:generate

Rodar as permissoes (ainda dentro do container)

    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
Rodar a migrate e seeder (Dentro do Container)

        php artisan migrate --seed

Apos isso saia do container e acesse:

        localhost:8080 (aplicação)
        localhost8081 (phpmyadmin)



