#include <bitset>
#include <byteswap.h>
#include "wiringPiI2C.h"
#include "BME280.h"
#include "TrimmingParameters.h"
#include "Exception/BadMethodCallException.h"

SensorSystem::BME280::BME280() {
    device = wiringPiI2CSetup(0x76);
    // Oversampling der humidity auf 1 setzen
    wiringPiI2CWriteReg8(device, 0xF2, 0x01);
    // Den Status auf Ein setzen und das Oversampling von Temp und Press auf 1. Aktiviert auch das Setzen des
    // Registers 0xF2
    wiringPiI2CWriteReg8(device, 0xF4, 0x27);
    trimmingParameters = new TrimmingParameters();
    tFine = nullptr;
}


float SensorSystem::BME280::ReadTemperature() {
    trimmingParameters->ReadTemperatureTrimmingParameters();
    unsigned int readTemperature = wiringPiI2CReadReg8(device, 0xFA) << 12;
    readTemperature += wiringPiI2CReadReg8(device, 0xFB) << 4;

    return (float)CalculateRealTemperature((long int)readTemperature);
}

float SensorSystem::BME280::ReadHumidity() {
    if (tFine == nullptr) {
        throw SensorSystem::BadMethodCallException("Temperature must be read before humidity");
    }

    trimmingParameters->ReadHumidityTrimmingParameters();
    long int readHumidity = bswap_16(wiringPiI2CReadReg16(device, 0xFD));
    long unsigned int realHumidity = CalculateRealHumidity(readHumidity);
    return (float) realHumidity;
}

float SensorSystem::BME280::ReadPressure() {
    trimmingParameters->ReadPressureTrimmingParameters();
    long int readPressure = bswap_16(wiringPiI2CReadReg16(device, 0xF7)) << 4;
    readPressure += (wiringPiI2CReadReg8(device, 0xF9) & 0b11110000) >> 4;


    return (float)CalculateRealPressure(readPressure);
}

// Die komischen Formeln von Bosch, die keiner versteht...
long int SensorSystem::BME280::CalculateRealTemperature(long int temperatureFromRegister) {
    long int var1, var2, T;
    var1 = ((((temperatureFromRegister >> 3) - ((long int)trimmingParameters->temperature1 << 1))) * ((long int)trimmingParameters->temperature2)) >> 11;
    var2 = (((((temperatureFromRegister >> 4) - ((long int)trimmingParameters->temperature1)) * ((temperatureFromRegister >> 4) - ((long int)trimmingParameters->temperature1))) >> 12) *
            ((long int)trimmingParameters->temperature3)) >> 14;
    long int fine = var1 + var2;
    tFine = &fine;

    return (*tFine * 5 + 128) >> 8;
}

long unsigned int SensorSystem::BME280::CalculateRealHumidity(long signed int humidityFromRegister) {
    long signed int v_x1_u32r;
    v_x1_u32r = (*tFine - ((long signed int)76800));
    v_x1_u32r = (((((humidityFromRegister << 14) - (((long signed int)trimmingParameters->humidity4) << 20) -
            (((long signed int)trimmingParameters->humidity5) * v_x1_u32r)) + ((long signed int)16384)) >> 15) *
                    (((((((v_x1_u32r * ((long signed int)trimmingParameters->humidity6)) >> 10) *
                    (((v_x1_u32r * ((long signed int)trimmingParameters->humidity3)) >> 11) + ((long signed int)32768))) >> 10) +
                    ((long signed int)2097152)) * ((long signed int)trimmingParameters->humidity2) + 8192) >> 14));
    v_x1_u32r = (v_x1_u32r - (((((v_x1_u32r >> 15) * (v_x1_u32r >> 15)) >> 7) * ((long signed int)trimmingParameters->humidity1)) >> 4));
    v_x1_u32r = (v_x1_u32r < 0 ? 0 : v_x1_u32r);
    v_x1_u32r = (v_x1_u32r > 419430400 ? 419430400 : v_x1_u32r);

    return (long unsigned int)(v_x1_u32r >> 12);
}

SensorSystem::BME280::~BME280() {
    delete trimmingParameters;
}

double SensorSystem::BME280::CalculateRealPressure(long signed int pressureFromRegister) {
    double var1, var2, p;
    var1 = ((double)*tFine/2.0) - 64000.0;
    var2 = var1 * var1 * ((double)trimmingParameters->pressure6) / 32768.0;
    var2 = var2 + var1 * ((double)trimmingParameters->pressure5) * 2.0;
    var2 = (var2/4.0)+(((double)trimmingParameters->pressure4) * 65536.0);
    var1 = (((double)trimmingParameters->pressure3) * var1 * var1 / 524288.0 +
            ((double)trimmingParameters->pressure2) * var1) / 524288.0;
    var1 = (1.0 + var1 / 32768.0)*((double)trimmingParameters->pressure1);
    if (var1 == 0.0)
    {
        return 0; // avoid exception caused by division by zero
    }
    p = 1048576.0 - (double)pressureFromRegister;
    p = (p - (var2 / 4096.0)) * 6250.0 / var1;
    var1 = ((double)trimmingParameters->pressure9) * p * p / 2147483648.0;
    var2 = p * ((double)trimmingParameters->pressure8) / 32768.0;
    return p + (var1 + var2 + ((double)trimmingParameters->pressure7)) / 16.0;
}

