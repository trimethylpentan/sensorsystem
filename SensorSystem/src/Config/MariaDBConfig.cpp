#include "MariaDBConfig.h"
#include "filesystem"
#include "./../../libs/simpleini/SimpleIni.h"

SensorSystem::MariaDBConfig::MariaDBConfig() {
    CSimpleIniA config;
    config.SetUnicode();
    SI_Error success = config.LoadFile((std::filesystem::current_path().string() + "/../config/mariadb.ini").c_str());
    if (success < 0) {
        throw std::exception();
    }
    host = config.GetValue("mariadb", "hostname", "");
    port = config.GetValue("mariadb", "port", "");
    username = config.GetValue("mariadb", "username", "");
    password = config.GetValue("mariadb", "password", "");
    database = config.GetValue("mariadb", "database", "");
}
