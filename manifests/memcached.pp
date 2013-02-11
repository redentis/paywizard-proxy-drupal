class memcached {
  package { "memcached":
    ensure => present,
  }
  package { "php5-memcached":
    ensure => present,
  }
}


