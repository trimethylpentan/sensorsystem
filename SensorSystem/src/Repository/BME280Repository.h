#pragma once

namespace MariaCpp {
    class Time;
}
namespace SensorSystem {
    class MariaDBConfig;
    class BME280Repository {
    public:
        BME280Repository();
        virtual ~BME280Repository();

        void  SaveMeasurement(float temperature, float humidity, float pressure);

    private:
        MariaDBConfig *config;

        static MariaCpp::Time GetCurrentTime();
    };
}
