require 'rubygems'

require 'net/http'
require 'json'
require 'syslog'

Syslog.open();
Syslog.info('start pma_register');
p "start pma_register"

pmshost = 'api.pne.cc'
pmahost = 'pne.cc'

http = Net::HTTP.new(pmshost, 80)

# ping 
response = http.request_get('/api/server/ping&host='+pmahost)
p "server ping"
if response.code == '200' then
  result =  JSON.parse(response.body)
  p result
  if result['result'] == false then
    respons = http.request_post('/api/server/add', 'host='+pmahost)
    p "add"
    if response.code != '200' then
      Syslog.err(response.body)
    end
  end
end

Syslog.info('end pma_register');
p "end pma_register"

