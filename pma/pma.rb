
require 'rubygems'

require 'net/https'
require 'json'
require 'syslog'
require 'fileutils'

Syslog.open()
Syslog.info('start pma')
p "start pma"
if File.exist?("/tmp/.pmalock") then
  Syslog.info('still run ')
  p "still run"
  Syslog.info('exit pma')
  p "exit pma"
  exit
end

FileUtils.touch('/tmp/.pmalock')

pmshost = 'pne.jp'
pmahost = `hostname`.chop

https = Net::HTTP.new(pmshost, 443)
https.use_ssl = true
https.ca_file = '/opt/sabakan/hosting/script/pma/ca-bundle.crt'
https.verify_mode = OpenSSL::SSL::VERIFY_PEER
https.verify_depth = 5

installDomains = Dir::entries('/var/www/sns/') - ['.', '..', 'munin.example.com', 'stopped', 'stoppedSNS']
# install or delete snss
response = https.get('/api/server/detail?host='+pmahost)
p response.body
if response.code == '200' then
  details =  JSON.parse(response.body)
  p "server detail"

  if details != [] then
    expectedDomains = details['domain']

    installTargetDomains = expectedDomains - installDomains
    p installTargetDomains
    installTargetDomains.each { |domain|
      p "install " + domain
      snsResponse = https.request_get('/api/sns/detail?domain='+domain)
      p "sns detail"
      p snsResponse.body
      if snsResponse.code == '200' then
        snsDetail = JSON.parse(snsResponse.body)
        p snsDetail
        userResult = ""
        adminResult = ""
        IO.popen('/opt/sabakan/hosting/script/autoinst/install.sh '+domain+' '+snsDetail['adminEmail']) do |io|
          while line = io.gets
            userResult = line.split(" ")[0]
            adminResult = line.split(" ")[1]
          end
          if userResult == nil || adminResult == nil then
            p "fail to install " + domain
            IO.popen('/opt/sabakan/autoinst/sns_delete.sh '+domain) do |io|
              while line = io.gets
              end
            end
          else
            passResponse = https.request_post('/api/sns/setpass', 'domain='+domain+'&mpass='+userResult+'&apass='+adminResult)
            break # take once time to install
          end
        end
      else
        p "fail to install " + domain
      end
    }

    #deleteTargetDomains = installDomains - expectedDomains
    #deleteTargetDomains.each { |domain|
    #  snsResponse = https.request_get('/api/sns/detail?domain='+domain)
    #  snsDetail = JSON.parse(snsResponse.body)
    #  if snsDetail['status'] == 'deleted' then
    #    installResult = open('| sns_delete.sh '+domain)
    #    p "sns delete"
    #  else
    #    p "fail to delete " + domain
    #    http.request_post('/api/server/event', 'eventType=error&message=deletefailed')
    #  end
    #}
  end
else
  Syslog.err(response.body)
  p response.body
end

Syslog.info('end pma');
p "end pma"
FileUtils.rm('/tmp/.pmalock')
