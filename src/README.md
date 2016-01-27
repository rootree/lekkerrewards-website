LekkerRewards
=======================

Introduction
------------

## To create entities (MappedSuperclass):

    php vendor/bin/doctrine  orm:convert-mapping --force --from-database --namespace "Application\\Entity\\"  annotation "module/Application/src/"

    php vendor/bin/doctrine orm:generate-entities --generate-annotations=true module/Application/src

## To create entities with Filter
    php vendor/bin/doctrine  orm:convert-mapping --force --from-database --namespace "Application\\Entity\\" --filter="Redeem" annotation "module/Application/src/"
    
    php vendor/bin/doctrine orm:generate-entities --filter="Redeem" --generate-annotations=true module/Application/src

# Generate language 

    touch module/Application/language/ru_RU.po && find module/Application/src/Application/Controller/ module/Application/src/Application/Service/ module/Application/view/ -name \*.phtml -o -name \*.php | xgettext -p  module/Application/language -o ru_RU.po -j -C -L PHP -ktranslate -ktr --from-code=UTF-8 -f -
    msgfmt -o module/Application/language/ru_RU.mo module/Application/language/ru_RU.po
    
    touch module/Application/language/en_US.po && find module/Application/src/Application/Controller/ module/Application/src/Application/Service/ module/Application/view/ -name \*.phtml -o -name \*.php | xgettext -p  module/Application/language -o en_US.po -j -C -L PHP -ktranslate -ktr --from-code=UTF-8 -f -
    msgfmt -o module/Application/language/en_US.mo module/Application/language/en_US.po

	touch module/Application/language/nl_NL.po && find module/Application/src/Application/Controller/ module/Application/src/Application/Service/ module/Application/view/ -name \*.phtml -o -name \*.php | xgettext -p  module/Application/language -o nl_NL.po -j -C -L PHP -ktranslate -ktr --from-code=UTF-8 -f -
    msgfmt -o module/Application/language/nl_NL.mo module/Application/language/nl_NL.po
    
# Deploy

    rsync -azP --del --exclude '.git' --exclude '.DS_Store' ./src/ webmaster@37.200.70.94:"/var/websites/lekkerrewards.nl/"
    scp -r ./src/. webmaster@37.200.70.94:"/var/websites/lekkerrewards.nl/"
    scp ./src/config/autoload/local.live.php webmaster@37.200.70.94:"/var/websites/lekkerrewards.nl/config/autoload/local.php"
    scp ./src/config/autoload/doctrine.live.php webmaster@37.200.70.94:"/var/websites/lekkerrewards.nl/config/autoload/doctrine.php"
    scp ./src/config/autoload/logs.live.php webmaster@37.200.70.94:"/var/websites/lekkerrewards.nl/config/autoload/logs.global.php"
    ssh webmaster@37.200.70.94
    cd /var/websites/lekkerrewards.nl/
    chmod -R a+r /var/websites/lekkerrewards.nl/public/. 
    chmod -R 777 /var/websites/lekkerrewards.nl/data/.
    chmod -R 777 /var/websites/lekkerrewards.nl/logs/.
    
# Access (9NAa28nb)

    ssh webmaster@37.200.70.94
    cd /var/websites/lekkerrewards.nl/

# Access to vagrant 

    vagrant ssh
    cd /vagrant/src/
    tail -f /var/log/apache2/error.log
    tail -f /var/websites/lekkerrewards.nl/logs/log_2
    tail -f /var/log/apache2/other_vhosts_access.log
    tail -f /var/log/apache2/access.log 

# Access to live 
 
 	ssh webmaster@37.200.70.94
    cd /var/websites/lekkerrewards.nl/
    tail -f /var/log/apache2/error.log
    tail -f /var/websites/lekkerrewards.nl/logs/log_2
    tail -f /var/log/apache2/other_vhosts_access.log
    tail -f /var/log/apache2/access.log 
    
# Run console

    php ./public/index.php generate-qrs <qty> 
    
# Useful commands

    ssh webmaster@37.200.70.94
    chmod 777 -R /var/websites/lekkerrewards.nl/data/DoctrineORMModule/.
    cd /var/websites/lekkerrewards.nl/dumps/ && /var/websites/lekkerrewards.nl/utils/mysql2sqlite.sh -u root -p111111 loyalty | sqlite3 database.sqlite
    ./utils/mysql2sqlite.sh -u root -p111111 loyalty | sqlite3 ./loyalty.db