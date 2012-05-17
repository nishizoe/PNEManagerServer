HOST="localhost"

curl "http://$HOST/api?type=server&action=ping&host=localhost"
curl "http://$HOST/api/server/add" -d "host=localhost"
curl "http://$HOST/api/server/list"
curl "http://$HOST/api/server/detail?host=localhost"

echo "waiting user input"

curl "http://$HOST/api/sns/apply" -d "domain=watanabe.pne.jp" -d "email=watanabe@tejimaya.com"

echo "waiting pma"

curl "http://$HOST/api/sns/detail?domain=watanabe.pne.jp"
curl "http://$HOST/api/server/detail?host=localhost"

echo "install"

curl "http://$HOST/api/server/update" -d "host=localhost" -d "domain=[\"watanabe.pne.jp\"]"

echo "pma_notity"

curl "http://$HOST/api/sns/setpass" -d "domain=watanabe.pne.jp" -d "mpass=password" -d "apass=password"
