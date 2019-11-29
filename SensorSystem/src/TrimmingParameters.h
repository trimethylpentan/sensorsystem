#pragma once

namespace SensorSystem {
    class TrimmingParameters {
    public:
        void ReadTemperatureTrimmingParameters();
        void ReadHumidityTrimmingParameters();
        void ReadPressureTrimmingParameters();

        unsigned short temperature1;
        signed short temperature2;
        signed short temperature3;

        unsigned char humidity1;
        signed short humidity2;
        unsigned char humidity3;
        signed short humidity4;
        signed short humidity5;
        signed char humidity6;

        unsigned short pressure1;
        signed short pressure2;
        signed short pressure3;
        signed short pressure4;
        signed short pressure5;
        signed short pressure6;
        signed short pressure7;
        signed short pressure8;
        signed short pressure9;

    private:
        bool temperatureLoaded = false;
        bool humidityLoaded = false;
        bool pressureLoaded = false;
    };
}

