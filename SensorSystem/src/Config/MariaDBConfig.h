#pragma once

#include <string>

namespace SensorSystem {
    class MariaDBConfig {
    public:
        MariaDBConfig();

        std::string host;
        std::string port;
        const char * username;
        const char * password;
        std::string database;
    };

}
