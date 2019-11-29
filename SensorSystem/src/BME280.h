#pragma once

namespace SensorSystem {
    class TrimmingParameters;
    class BME280 {
    public:
        virtual ~BME280();
        BME280();

        float ReadTemperature();
        float ReadHumidity();
        float ReadPressure();

    private:
        long CalculateRealTemperature(long int temperatureFromRegister);
        long unsigned int CalculateRealHumidity(long signed int humidityFromRegister);
        double CalculateRealPressure(long signed int pressureFromRegister);
        TrimmingParameters *trimmingParameters;

        int device;
        long int* tFine;
    };
}
