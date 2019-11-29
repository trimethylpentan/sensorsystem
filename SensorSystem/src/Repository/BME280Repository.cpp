#include <iostream>
#include <mariacpp/connection.hpp>
#include <mariacpp/uri.hpp>
#include <mariacpp/time.hpp>
#include <mariacpp/exception.hpp>
#include <mariacpp/prepared_stmt.hpp>
#include <memory>
#include <ctime>
#include <iomanip>
#include "BME280Repository.h"
#include "../Config/MariaDBConfig.h"

using namespace std;
using namespace MariaCpp;

SensorSystem::BME280Repository::BME280Repository() {
    config = new MariaDBConfig();
}

SensorSystem::BME280Repository::~BME280Repository() {
    delete config;
}

void SensorSystem::BME280Repository::SaveMeasurement(float temperature, float humidity, float pressure) {
    Connection connection = Connection();
    try {
        string uri = "tcp://" + config->host + ":" + config->port + "/" + config->database;
        const char *user = config->username;
        const char *password = config->password;

        connection.connect(Uri(uri), user, password);

        string query = "INSERT INTO measurements (timestamp, color, humidity, temperature, air_pressure)"
                       " VALUES (?, ?, ?, ?, ?)";
        unique_ptr<PreparedStatement> statement(connection.prepare(query));
        statement->setDateTime(0, GetCurrentTime());
        statement->setInt(1, 1);
        statement->setFloat(2, humidity);
        statement->setFloat(3, temperature);
        statement->setFloat(4, pressure);

        statement->execute();

    } catch (Exception &exception) {
        std::cerr << exception << std::endl;
    }

    connection.close();
}

MariaCpp::Time SensorSystem::BME280Repository::GetCurrentTime() {
    auto now = time(nullptr);
    auto nowGM = *gmtime(&now);
    ostringstream stream;
    stream << put_time(&nowGM, "%Y-%m-%d %H:%M:%S");
    string nowAtom = stream.str();
    return Time(nowAtom);
}
