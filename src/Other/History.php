<?php
namespace Sesame\Other;

use Sesame\Other\Utility;

class History {
    public $hisotry;

    public function __construct(array $hisotry) {
        $this->hisotry = $hisotry;
    }

    /**
     *
     * 変換した配列を返す
     *
     * @return array
     */
    public function toArray(): array {
        return $this->convert($this->hisotry);
    }

    /**
     * 変換する
     *
     * @param array $history
     * @return array
     */
    public function convert(array $history): array {
        $result = [];

        foreach ($history as $_history) {
            $datetime = Utility::datetimeByTimestamp($_history->timeStamp);
            $date = $datetime;

            $result[] = [
                'id' => $_history->recordID,
                'type' => $this->getStatus($_history->type),
                'date' => $date,
            ];
        }
        return $result;
    }

    /**
     * セサミステータスの取得
     *
     * @param int $status_id
     * @return string
     */
    public function getStatus(int $status_id): string {
        $list = [
            '0' => 'none',
            '1' => 'bleLock',
            '2' => 'bleUnLock',
            '3' => 'timeChanged',
            '4' => 'autoLockUpdated',
            '5' => 'mechSettingUpdated',
            '6' => 'autoLock',
            '7' => 'manualLocked',
            '8' => 'manualUnlocked',
            '9' => 'manualElse',
            '10' => 'driveLocked',
            '11' => 'driveUnlocked',
            '12' => 'driveFailed',
            '13' => 'bleAdvParameterUpdated',
            '14' => 'wm2Lock',
            '15' => 'wm2Unlock',
            '16' => 'webLock',
            '17' => 'webUnlock',
        ];
        return $list[$status_id] ?? '';
    }
}
