class mysql {
  package { "mysql-server":
    ensure => present,
  }
  package { "mysql-client":
    ensure => present,
  }
}

class php5-mysql {
  require mysql
  require apache
  package { "php5-mysql":
    ensure => present,
  }
}

