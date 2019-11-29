#include "BadMethodCallException.h"

#include <utility>

using namespace std;

SensorSystem::BadMethodCallException::BadMethodCallException(string exceptionMessage) {
    message = move(exceptionMessage);
}

string SensorSystem::BadMethodCallException::what() {
    return message;
}

