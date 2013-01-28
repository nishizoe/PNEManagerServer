require 'rubygems'

require 'mysql2'


open('/opt/sabakan/autoinst/db.conf') { |file|
  properties = file.readlines
  user = properties[0].split('=')[1].strip
  pass = properties[1].split('=')[1].strip
  host = properties[2].split('=')[1].strip

  sum = 0 

  open('/opt/sabakan/autoinst/installed_domain_list.txt').each { |sns| 
      dbname = sns.strip.gsub(/\./, '_')
      db = Mysql2::Client.new(
          :host => host, 
          :username => user, 
          :password => pass, 
          :database => dbname
      )   
  
      rows = db.query('select count(*) from member where is_active = 1')
      rows.each {|row|
          c = row['count(*)']
          print dbname, " ", c, "\n"
          sum += c
      }   
  }
  
  print sum, "\n"
}
