#include "TrimmingParameters.h"
#include "wiringPiI2C.h"
#include <byteswap.h>

void SensorSystem::TrimmingParameters::ReadTemperatureTrimmingParameters() {
    if (temperatureLoaded) {
        return;
    }

    int fileHandle = wiringPiI2CSetup(0x76);
    // Auslesen der Kalibrierungswerte.
    temperature1 =  wiringPiI2CReadReg16(fileHandle, 0x88);
    temperature2 = wiringPiI2CReadReg16(fileHandle, 0x8A);
    temperature3 = wiringPiI2CReadReg16(fileHandle, 0x8C);

    temperatureLoaded = true;
}

void SensorSystem::TrimmingParameters::ReadHumidityTrimmingParameters() {
    if (humidityLoaded) {
        return;
    }

    int fileHandle = wiringPiI2CSetup(0x76);
    // Auslesen der Kalibrierungswerte.
    humidity1 = wiringPiI2CReadReg8(fileHandle, 0xA1);
    humidity2 = bswap_16(wiringPiI2CReadReg16(fileHandle, 0xE1));
    humidity3 = wiringPiI2CReadReg8(fileHandle, 0xE3);

    short register4 = wiringPiI2CReadReg8(fileHandle, 0xE4);
    short register5 = wiringPiI2CReadReg8(fileHandle, 0xE5);
    short register6 = wiringPiI2CReadReg8(fileHandle, 0xE6);

    humidity4 = (register4 << 4) + (register5 & 0b00001111);
    humidity5 = ((register5 & 0b11110000) >> 4) + (register6 << 4);
    humidity6 = wiringPiI2CReadReg8(fileHandle, 0xE7);

    humidityLoaded = true;
}

void SensorSystem::TrimmingParameters::ReadPressureTrimmingParameters() {
    if (pressureLoaded) {
        return;
    }

    int fileHandle = wiringPiI2CSetup(0x76);

    // Auslesen der Kalibrierungswerte
    pressure1 = wiringPiI2CReadReg16(fileHandle, 0x8E);
    pressure2 = wiringPiI2CReadReg16(fileHandle, 0x90);
    pressure3 = wiringPiI2CReadReg16(fileHandle, 0x92);
    pressure4 = wiringPiI2CReadReg16(fileHandle, 0x94);
    pressure5 = wiringPiI2CReadReg16(fileHandle, 0x96);
    pressure6 = wiringPiI2CReadReg16(fileHandle, 0x98);
    pressure7 = wiringPiI2CReadReg16(fileHandle, 0x9A);
    pressure8 = wiringPiI2CReadReg16(fileHandle, 0x9C);
    pressure9 = wiringPiI2CReadReg16(fileHandle, 0x9E);

    pressureLoaded = true;
}
