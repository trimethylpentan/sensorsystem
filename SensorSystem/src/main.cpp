#include <unistd.h>
#include "BME280.h"
#include "Repository/BME280Repository.h"

int main() {
    SensorSystem::BME280 bme;
    SensorSystem::BME280Repository repository;
    while (sleep(10) == 0) {
        float temperature = bme.ReadTemperature() / 100;
        float humidity = bme.ReadHumidity() / 1024;
        float pressure = bme.ReadPressure() / 100;
        repository.SaveMeasurement(temperature, humidity, pressure);
    }
    return 0;
}