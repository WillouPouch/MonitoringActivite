#ifndef ZONETRACKER_H
#define ZONETRACKER_H

#include <QtCore>
#include <opencv2/opencv.hpp>
#include <opencv2/imgproc.hpp>
#include <opencv2/highgui.hpp>
#include <opencv2/videoio.hpp>
#include <vector>

class ZoneTracker : public QObject {
    Q_OBJECT

public:
    explicit ZoneTracker(QObject *parent = Q_NULLPTR, bool debug = false);

private:
    QThread m_thread;
    QString m_pos_patient;
    cv::VideoCapture m_cap;
    bool m_debug;

private slots:
        void start_zone_tracking();

signals:
    void zone_changed(QString);
};

#endif // ZONETRACKER_H
