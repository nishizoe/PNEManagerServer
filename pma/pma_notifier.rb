require 'rubygems'

require 'net/http'
require 'json'
require 'syslog'

pmshost = 'api.pne.cc'
pmahost = 'pne.cc'

Syslog.open();
Syslog.info('start pma_notifier');
p "start pma_notifer"
if File.exist?("/tmp/.pmalock") then
  Syslog.info('still run ');
  p "still runn"
  Syslog.info('exit pma_notifier');
  p "exit pma_notifier"
  exit;
end

installDomains = Dir::entries('/var/www/sns/') - ['.', '..']

http = Net::HTTP.new(pmshost, 80)

res = http.request_post('/api/server/update', 'host='+pmahost+'&domain='+installDomains.to_json)

puts res.body

Syslog.info('end pma_notifier');
p "end pma_notifier"
