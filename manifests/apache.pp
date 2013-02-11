class apache {
  exec { "apt_update":
    command => "apt-get update",
    path    => "/usr/bin"
  }
  package { "php5":
    ensure => present,
  }
  package { "libapache2-mod-php5":
    ensure => present,
  }
  package { "apache2":
    ensure => present,
  }
  service { "apache2":
    ensure => running,
    require => Package["apache2"],
  }
}

