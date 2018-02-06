#ifndef TVTRACKER_H
#define TVTRACKER_H

#include <QtCore>
#include <opencv2/opencv.hpp>
#include <opencv2/imgproc.hpp>
#include <opencv2/highgui.hpp>
#include <opencv2/videoio.hpp>

class TvTracker : public QObject {
    Q_OBJECT

public:
    explicit TvTracker(QObject *parent = Q_NULLPTR, bool debug = false);

private:
    QThread m_thread;
    cv::VideoCapture m_cap;
    bool m_tv_on;
    bool m_tv_on_old;
    bool m_debug;

private slots:
        void start_tv_tracking();

signals:
    void tv_on();
    void tv_off();
};

#endif // TVTRACKER_H
