#! /bin/bash

echo migrate.
php yii migrate/up

echo fixtures
yes | php yii fixture/load User
#php yii fixture/load PublicProject зависит от task
yes | php yii fixture/load Task
yes | php yii fixture/load Project
